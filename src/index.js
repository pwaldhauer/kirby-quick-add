import QuickAdd from "./components/sections/QuickAdd.vue";
import QuickAddImage from "./components/sections/QuickAddImage.vue";

panel.plugin("pwaldhauer/quick-add", {
    sections: {
        'quickadd': QuickAdd,
        'quickaddimage': QuickAddImage
    }
});
