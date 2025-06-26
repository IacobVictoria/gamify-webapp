export function useRomanianDatetimeFormat() {
    function formatToDatetimeLocal(date) {
        const now = new Date();
        const clickedDate = new Date(date);

        // Obține ora curentă din București
        const formatter = new Intl.DateTimeFormat("ro-RO", {
            timeZone: "Europe/Bucharest",
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        });

        const parts = formatter.formatToParts(now);
        const dateParts = Object.fromEntries(
            parts.map((p) => [p.type, p.value])
        );

        clickedDate.setHours(+dateParts.hour);
        clickedDate.setMinutes(+dateParts.minute);

        // Ajustează la fusul orar local pentru datetime-local (fără Z)
        const localISO = new Date(
            clickedDate.getTime() - clickedDate.getTimezoneOffset() * 60000
        )
            .toISOString()
            .slice(0, 16); // format: "YYYY-MM-DDTHH:MM"

        return localISO;
    }

    return {
        formatToDatetimeLocal,
    };
}
