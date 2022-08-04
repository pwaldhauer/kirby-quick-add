<template>
    <div class="panel">
        <k-dropzone @drop="drop">
            <k-headline size="medium" class="headline">{{headline}}</k-headline>

            <p class="inner">{{text}}</p>
            <k-upload ref="uploader" @success="onUploadSuccess"/>
        </k-dropzone>
    </div>

</template>

<style>


.headline {

  background: var(--color-gray-300);
  padding: 10px 20px;
}

.inner {
  background: var(--color-background);
  padding: 10px 20px;
}

.panel {
  background: var(--color-white);
  border-radius: var(--rounded);
  box-shadow: var(--shadow);
  padding-bottom: 0;
}

</style>

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
                      this.$go(`pages/${this.postPath.replace(/\//g, '+')}`);
                    })
                })
            },

            drop(files) {
                const firstFile = files[0];

                this.createShallowPost(firstFile.name).then(data => {
                    this.postPath = data.id;
                    this.$refs.uploader.drop(files, {
                        url: this.$urls.api + '/' + this.$api.pages.url(this.postPath, "files"),
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
