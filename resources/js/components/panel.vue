<template>
    <div v-if="panel.fields.length > 0" v-show="visibleFieldsCount > 0">
        <Heading :level="1" :class="panel.helpText ? 'mb-2' : 'mb-3'" :dusk="`${dusk}-heading`">
            {{ panel.name }}
        </Heading>

        <p v-if="panel.helpText" class="text-gray-500 text-sm font-semibold italic mb-3" v-html="panel.helpText" />

        <component v-for="(field, index) in panel.fields" :index="index" :key="index"
            :is="`${panel.mode}-${field.component}`" :errors="validationErrors" :resource-id="resourceId"
            :resource-name="resourceName" :related-resource-name="relatedResourceName"
            :related-resource-id="relatedResourceId" :field="field" :via-resource="viaResource"
            :via-resource-id="viaResourceId" :via-relationship="viaRelationship"
            :shown-via-new-relation-modal="shownViaNewRelationModal" :form-unique-id="formUniqueId" :mode="mode"
            @field-shown="handleFieldShown" @field-hidden="handleFieldHidden" @field-changed="$emit('field-changed')"
            @file-deleted="handleFileDeleted" @file-upload-started="$emit('file-upload-started')"
            @file-upload-finished="$emit('file-upload-finished')" :show-help-text="showHelpText" />
    </div>
</template>

<script>
import { HandlesPanelVisibility, mapProps } from 'laravel-nova'

export default {
    name: 'FormPanel',

    mixins: [HandlesPanelVisibility],

    emits: [
        'field-changed',
        'update-last-retrieved-at-timestamp',
        'file-deleted',
        'file-upload-started',
        'file-upload-finished',
    ],

    props: {
        ...mapProps(['mode']),
        shownViaNewRelationModal: { type: Boolean, default: false },
        showHelpText: { type: Boolean, default: false },
        panel: { type: Object, required: true },
        name: { default: 'Panel' },
        dusk: { type: String },
        fields: { type: Array, default: [] },
        formUniqueId: { type: String },
        validationErrors: { type: Object, required: true },
        resourceName: { type: String, required: true },
        resourceId: { type: [Number, String] },
        relatedResourceName: { type: String },
        relatedResourceId: { type: [Number, String] },
        viaResource: { type: String },
        viaResourceId: { type: [Number, String] },
        viaRelationship: { type: String },
    },
    methods: {
        handleFileDeleted() {
            this.$emit('update-last-retrieved-at-timestamp')
        },
    },
}
</script>
