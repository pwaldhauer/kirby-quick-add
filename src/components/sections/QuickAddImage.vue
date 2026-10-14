<template>
	<k-section :headline="headline" class="k-quick-add-image-section">
		<k-dropzone :disabled="isLoading" @drop="drop">
			<k-empty icon="upload" layout="cards">
				{{ text }}
			</k-empty>
		</k-dropzone>
	</k-section>
</template>

<script>
export default {
	data() {
		return {
			headline: "",
			text: "",
			dragText: "",
			target: "",
			template: "",
			isLoading: false
		};
	},

	async created() {
		const response = await this.load();

		this.headline = response.headline;
		this.text = response.text;
		this.dragText = response.dragText;
		this.target = response.target;
		this.template = response.template;
	},

	methods: {
		async drop(files) {
			this.isLoading = true;

			try {
				const page = await this.createShallowPost(files[0].name);
				this.upload(page, files);
			} catch (error) {
				this.$panel.error(error);
				this.isLoading = false;
			}
		},

		upload(page, files) {
			const upload = this.$panel.upload;

			upload.open(files, {
				url:
					this.$panel.urls.api +
					"/" +
					this.$panel.api.pages.url(page.id, "files"),
				multiple: true,
				on: {
					done: (uploaded) => this.onUploadDone(page, uploaded),
					cancel: () => (this.isLoading = false)
				}
			});

			// start uploading right away instead of waiting
			// for the user to confirm the upload dialog
			this.$panel.dialog.isLoading = true;
			upload.submit().finally(() => (this.$panel.dialog.isLoading = false));
		},

		async onUploadDone(page, uploaded) {
			try {
				const text = uploaded
					.map(({ filename }) => this.dragText.replace("[FILENAME]", filename))
					.join("\n");

				await this.$panel.api.pages.update(page.id, { text });

				this.$panel.view.open(this.$panel.api.pages.link(page.id));
			} catch (error) {
				this.$panel.error(error);
			} finally {
				this.isLoading = false;
			}
		},

		createShallowPost(title) {
			const target = this.target.replace(
				"[YEAR]",
				new Date().getFullYear()
			);

			return this.$panel.api.pages.create(target, {
				slug: this.$helper.slug(title),
				template: this.template,
				content: {
					title: title,
					text: ""
				}
			});
		}
	}
};
</script>
