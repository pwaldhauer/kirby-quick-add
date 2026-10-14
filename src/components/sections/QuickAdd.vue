<template>
	<k-section :headline="headline" class="k-quick-add-section">
		<div class="k-quick-add-box">
			<k-form
				:fields="fields"
				:value="values"
				@input="values = $event"
				@submit="submit"
			>
				<template #footer>
					<footer class="k-quick-add-footer">
						<k-button
							icon="check"
							type="submit"
							variant="filled"
							size="sm"
							:disabled="isLoading"
						>
							Create
						</k-button>
						<k-button
							icon="add"
							variant="filled"
							theme="positive"
							size="sm"
							:disabled="isLoading"
							@click="publish"
						>
							Publish
						</k-button>
					</footer>
				</template>
			</k-form>
		</div>
	</k-section>
</template>

<script>
export default {
	data() {
		return {
			headline: "",
			target: "",
			templates: [],
			isLoading: false,
			values: {
				title: "",
				template: "",
				text: ""
			}
		};
	},

	computed: {
		fields() {
			return {
				title: {
					label: "Title",
					type: "text",
					width: "1/2"
				},
				template: {
					label: "Template",
					type: "select",
					width: "1/2",
					empty: false,
					options: this.templates.map((t) => ({ value: t, text: t }))
				},
				text: {
					label: "Text",
					type: "textarea",
					buttons: false
				}
			};
		}
	},

	async created() {
		const response = await this.load();

		this.headline = response.headline;
		this.target = response.target;
		this.templates = response.templates;
		this.values.template = response.defaultTemplate;
	},

	methods: {
		publish() {
			this.createPage(true);
		},

		submit() {
			this.createPage();
		},

		async createPage(publish = false) {
			const target = this.target.replace(
				"[YEAR]",
				new Date().getFullYear()
			);

			this.isLoading = true;

			try {
				const page = await this.$panel.api.pages.create(target, {
					slug: this.$helper.slug(this.values.title),
					template: this.values.template,
					content: {
						title: this.values.title,
						text: this.values.text
					}
				});

				if (publish) {
					await this.$panel.api.pages.changeStatus(page.id, "listed");
				}

				this.$panel.view.open(this.$panel.api.pages.link(page.id));
			} catch (error) {
				this.$panel.error(error);
			} finally {
				this.isLoading = false;
			}
		}
	}
};
</script>

<style>
.k-quick-add-box {
	background: light-dark(var(--color-white), var(--color-gray-850));
	border-radius: var(--rounded);
	box-shadow: var(--shadow);
	padding: var(--spacing-6);
}

.k-quick-add-footer {
	display: flex;
	justify-content: space-between;
	margin-top: var(--spacing-6);
}
</style>
