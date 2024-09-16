<script>
import Switch from './switch.vue';
import { Localization } from 'laravel-nova'

export default {
    mixins: [Localization],
    props: {
        policy: {
            type: String,
            required: true
        },
        modelValue: {
            type: Array,
            required: false,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    emits: ['update:modelValue'],
    components: {
        Switch
    },
    computed: {
        value: {
            get() {
                if (!Array.isArray(this.modelValue)) {
                    return false;
                }
                return this.modelValue.some((v) => v === this.policy)
            },
            set(value) {
                if (this.disabled) {
                    return;
                }
                if (value) {
                    this.$emit('update:modelValue', [...this.modelValue, this.policy]);
                } else {
                    this.$emit('update:modelValue', this.modelValue.filter((v) => v !== this.policy));
                }
            }
        }
    }
}
</script>
<template>
    <label class="flex flex-row flex-wrap justify-between items-center">
        <span class="flex-none ns-font-bold">{{ __(policy) }}</span>
        <span>
            <Switch :disabled="disabled" class="grow" v-model="value" />
        </span>
    </label>
</template>
