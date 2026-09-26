/**
 * Quick date presets for the report filter bar. Clicking a preset only fills
 * the date_from/date_to inputs — it never submits the form — so any other
 * selected filter stays intact. Tracks the applied preset to keep a highlight
 * until the user edits a date field.
 */
export default function () {
    return {
        selected: null,

        apply(from, to) {
            this.$refs.from.value = from;
            this.$refs.to.value = to;
            this.selected = `${from}|${to}`;
        },
    };
}