<script>
    const otpBoxes = document.querySelectorAll('#otpInputs input');
    const otpForm = document.getElementById('otpForm');

    otpBoxes.forEach((box, index) => {
        box.addEventListener('input', () => {
            box.value = box.value.replace(/\D/g, '').slice(0, 1);

            if (box.value && index < otpBoxes.length - 1) {
                otpBoxes[index + 1].focus();
            }
        });

        box.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && !box.value && index > 0) {
                otpBoxes[index - 1].focus();
            }

            if (event.key === 'ArrowLeft' && index > 0) {
                otpBoxes[index - 1].focus();
            }

            if (event.key === 'ArrowRight' && index < otpBoxes.length - 1) {
                otpBoxes[index + 1].focus();
            }
        });

        box.addEventListener('paste', (event) => {
            const code = event.clipboardData
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, otpBoxes.length);

            if (!code) {
                return;
            }

            event.preventDefault();

            [...code].forEach((digit, digitIndex) => {
                otpBoxes[digitIndex].value = digit;
            });

            otpBoxes[Math.min(code.length, otpBoxes.length) - 1].focus();
        });
    });

    otpForm.addEventListener('submit', () => {
        document.getElementById('otpValue').value =
            [...otpBoxes].map((box) => box.value).join('');
    });
</script>
