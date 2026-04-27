const flashMessages = Array.isArray(window.flashMessages)
  ? window.flashMessages
  : [];

const iconByCategory = {
  success: "success",
  error: "error",
  warning: "warning",
  info: "info",
};

const SAVED_INFO_KEY = "commentFormSavedInfo";

async function showFlashAlerts() {
  for (const [category, message] of flashMessages) {
    await Swal.fire({
      icon: iconByCategory[category] || "info",
      title: category === "success" ? "Berhasil" : "Informasi",
      text: message,
      confirmButtonText: "OK",
    });
  }
}

function readSavedInfo() {
  try {
    const raw = localStorage.getItem(SAVED_INFO_KEY);
    if (!raw) {
      return null;
    }

    const parsed = JSON.parse(raw);
    if (typeof parsed !== "object" || parsed === null) {
      return null;
    }

    return {
      name: typeof parsed.name === "string" ? parsed.name : "",
      email: typeof parsed.email === "string" ? parsed.email : "",
      website: typeof parsed.website === "string" ? parsed.website : "",
    };
  } catch {
    return null;
  }
}

function saveInfo(data) {
  localStorage.setItem(SAVED_INFO_KEY, JSON.stringify(data));
}

function clearSavedInfo() {
  localStorage.removeItem(SAVED_INFO_KEY);
}

function initSavedInfoForm() {
  const form = document.getElementById("comment-form");
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const websiteInput = document.getElementById("website");
  const saveInfoCheckbox = document.getElementById("save-info");

  if (!form || !nameInput || !emailInput || !websiteInput || !saveInfoCheckbox) {
    return;
  }

  const savedInfo = readSavedInfo();
  if (savedInfo) {
    nameInput.value = savedInfo.name;
    emailInput.value = savedInfo.email;
    websiteInput.value = savedInfo.website;
    saveInfoCheckbox.checked = true;
  }

  form.addEventListener("submit", () => {
    if (saveInfoCheckbox.checked) {
      saveInfo({
        name: nameInput.value.trim(),
        email: emailInput.value.trim(),
        website: websiteInput.value.trim(),
      });
      return;
    }

    clearSavedInfo();
  });
}

if (flashMessages.length > 0) {
  showFlashAlerts();
}

initSavedInfoForm();
