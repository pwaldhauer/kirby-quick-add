<template>
    <div style="background-color: #dfdfdf; margin-bottom: 2rem; padding: 2rem;">
        <k-headline size="medium" style="margin-bottom: 2rem;">{{headline}}</k-headline>

        <k-form
                :fields="fields"
                v-model="values"
                @submit="submit"
        >

            <footer slot="footer" style="margin-top: 2rem;">
                <k-button
                        icon="check"
                        type="submit"
                        theme="positive">
                    Create
                </k-button>
            </footer>

        </k-form>

    </div>

</template>

<script>
    export default {
        methods: {
            submit() {
                const target = this.target.replace('[YEAR]', this.$library.dayjs().format("YYYY"));

                this.$api.pages.create(target, {
                    slug: this.$helper.slug(this.values.title),
                    title: this.values.title,
                    template: this.values.template,
                    content: {
                        title: this.values.title,
                        text: this.values.text
                    }
                }).then(data => {
                    this.$router.push({name: 'Page', params: {path: data.id.replace(/\//g, '+')}});
                })
            },

        },

        data() {
            return {
                headline: '',
                target: '',
                templates: [],

                values: {
                    title: '',
                    template: '',
                    text: '',
                },
                fields: {}
            }
        },

        computed: {
            templateOptions() {
                return this.templates.map(t => {
                    return {value: t, text: t}
                })
            }
        },

        created() {
            this.load().then(response => {
                this.headline = response.headline;
                this.target = response.target;
                this.templates = response.templates;
                this.values.template = response.defaultTemplate;

                this.fields = {
                    title: {
                        label: "Title",
                        type: "text",
                        width: "1/2"
                    },
                    template: {
                        label: "Template",
                        type: "select",
                        width: "1/2",
                        options: this.templateOptions
                    },
                    text: {
                        label: "Text",
                        type: "textarea",
                        buttons: false
                    }
                }
            });
        }
    };
</script>