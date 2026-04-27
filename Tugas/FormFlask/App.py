import os
import smtplib
from email.message import EmailMessage

from flask import Flask, flash, redirect, render_template, request, url_for

try:
    from dotenv import load_dotenv
except ImportError:
    load_dotenv = None

if load_dotenv is not None:
    load_dotenv()

app = Flask(__name__, template_folder="Views", static_folder="Assets")
app.secret_key = os.getenv("FLASK_SECRET_KEY", "dev-secret-key")


def _to_bool(value: str, default: bool = False) -> bool:
    if value is None:
        return default
    return value.strip().lower() in {"1", "true", "yes", "on"}


def send_comment_email(form_data: dict) -> None:
    smtp_host = os.getenv("SMTP_HOST")
    smtp_port = int(os.getenv("SMTP_PORT", "587"))
    smtp_username = os.getenv("SMTP_USER")
    smtp_password = os.getenv("SMTP_PASS")
    smtp_use_tls = _to_bool(os.getenv("SMTP_USE_TLS", "true"), True)
    smtp_use_ssl = _to_bool(os.getenv("SMTP_USE_SSL", "false"), False)

    recipient = os.getenv("MAIL_TO", smtp_username)
    sender = os.getenv("MAIL_FROM", smtp_username)

    if not smtp_host or not sender or not recipient:
        raise RuntimeError(
            "Konfigurasi SMTP belum lengkap. Pastikan SMTP_HOST, SMTP_USER, dan MAIL_TO/MAIL_FROM sudah diatur."
        )

    msg = EmailMessage()
    msg["Subject"] = os.getenv("MAIL_SUBJECT", "New Comment Form Submission")
    msg["From"] = sender
    msg["To"] = recipient

    body = (
        "Ada input baru dari form komentar:\n\n"
        f"Comment : {form_data['comment']}\n"
        f"Name    : {form_data['name']}\n"
        f"Email   : {form_data['email']}\n"
        f"Website : {form_data['website'] or '-'}\n"
        f"Save Info Checked : {'Yes' if form_data['save_info'] else 'No'}\n"
    )
    msg.set_content(body)

    if smtp_use_ssl:
        with smtplib.SMTP_SSL(smtp_host, smtp_port) as server:
            if smtp_username and smtp_password:
                server.login(smtp_username, smtp_password)
            server.send_message(msg)
    else:
        with smtplib.SMTP(smtp_host, smtp_port) as server:
            if smtp_use_tls:
                server.starttls()
            if smtp_username and smtp_password:
                server.login(smtp_username, smtp_password)
            server.send_message(msg)


@app.route("/", methods=["GET", "POST"])
def index():
    if request.method == "POST":
        form_data = {
            "comment": request.form.get("comment", "").strip(),
            "name": request.form.get("name", "").strip(),
            "email": request.form.get("email", "").strip(),
            "website": request.form.get("website", "").strip(),
            "save_info": request.form.get("save-info") == "on",
        }

        if not form_data["comment"] or not form_data["name"] or not form_data["email"]:
            flash("Kolom Comment, Name, dan Email wajib diisi.", "error")
            return render_template("index.html")

        try:
            send_comment_email(form_data)
        except Exception as exc:
            flash(f"Gagal mengirim email: {exc}", "error")
            return render_template("index.html")

        flash("Komentar berhasil dikirim.", "success")
        return redirect(url_for("index"))

    return render_template("index.html")


if __name__ == "__main__":
    app.run(debug=True)
