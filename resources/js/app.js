import "./bootstrap";
document.addEventListener('DOMContentLoaded', () => {
    const textarea = document.getElementById("messageTextarea");
    const charCount = document.getElementById("charCount");
    const limit = 255;

    if (textarea && charCount) {
        textarea.addEventListener("input", () => {
            const currentLength = textarea.value.length;
            charCount.textContent = `${currentLength} / ${limit}`;
        });
    }
});
