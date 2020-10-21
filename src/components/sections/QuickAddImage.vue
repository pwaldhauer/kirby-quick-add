<template>
    <div style="background-color: #dfdfdf; margin-bottom: 2rem; padding: 2rem;">
        <k-dropzone @drop="drop">
            <k-headline size="medium" style="margin-bottom: 2rem;">{{headline}}</k-headline>

            <p style="padding-top: 2rem; padding-bottom: 2rem;">{{text}}</p>
            <k-upload ref="uploader" @success="onUploadSuccess"/>
        </k-dropzone>
    </div>

</template>

<script>
    export default {
        methods: {
            onUploadSuccess() {
                this.$api.pages.files(this.postPath.replace(/\//g, '+')).then(({data}) => {
                    const texts = data.map(({filename}) => {
                        return this.dragText.replace('[FILENAME]', filename);
                    });

                    this.$api.pages.update(this.postPath.replace(/\//g, '+'), {
                        text: texts.join("\n")
                    }).then(() => {
                        this.$router.push({name: 'Page', params: {path: this.postPath.replace(/\//g, '+')}});
                    })
                })
            },

            drop(files) {
                const firstFile = files[0];

                this.createShallowPost(firstFile.name).then(data => {
                    this.postPath = data.id;
                    this.$refs.uploader.drop(files, {
                        url: window.panel.api + '/' + this.$api.pages.url(this.postPath, "files"),
                        multiple: true,
                    })
                })
            },


            createShallowPost(title) {
                const target = this.target.replace('[YEAR]', this.$library.dayjs().format("YYYY"));

                return this.$api.pages.create(target, {
                    slug: this.$helper.slug(title),
                    title: title,
                    template: this.template,
                    content: {
                        title: title,
                        text: ''
                    }
                })
            },

        },

        data() {
            return {
                postPath: null,
                headline: '',
                text: '',
                dragText: '',
                target: '',
                template: '',

                fields: {}
            }
        },

        created() {
            this.load()
                .then(response => {
                    this.headline = response.headline;
                    this.text = response.text;
                    this.dragText = response.dragText;
                    this.target = response.target;
                    this.template = response.template;
                });
        }
    };
</script>
