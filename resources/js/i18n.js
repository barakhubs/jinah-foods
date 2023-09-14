import en from "./languages/en.json";
import { createI18n } from "vue-i18n";

const i18n = createI18n({
    legacy: false,
    locale: "en",
    fallbackLocale: "en",
    messages: {
        en: en,
    }
});

export default i18n;


