<script>
import Policy from './policy.vue';
import Switch from './switch.vue';
import { Localization } from 'laravel-nova'


export default {
    mixins: [Localization],
    props: {
        resource: {
            type: Object,
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
        Policy,
        Switch
    },
    computed: {
        policies() {
            if (this.showAll) {
                return this.resource.policies
            }

            return this.resource.policies.filter((_, i) => i < 4)
        },
        value: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        },
        selectAllPolicy: {
            get() {
                return this.resource.policies.filter((policy) => this.value.includes(policy)).length === this.resource.policies.length
            },
            set(value) {
                if (this.disabled) {
                    return;
                }
                if (value) {
                    this.value = [
                        ...this.value,
                        ...this.resource.policies.filter((policy) => !this.value.includes(policy))
                    ]
                } else {
                    this.value = this.value.filter((v) => !this.resource.policies.includes(v))
                }
            }
        }

    },
    data() {
        return {
            showAll: false
        }
    }
}
</script>
<template>
    <div class="ns-flex-none ns-w-full">
        <Card class="ns-snap-start ns-snap-always">
            <div
                class="px-4 py-5 sm:px-6 flex flex-row items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <label :for="`id_${resource.prefix}`" class="text-lg font-medium">{{ __(resource.name) }}</label>
                <Switch :disabled="disabled" :id="`id_${resource.prefix}`" v-model="selectAllPolicy" />
            </div>
            <div class="ns-px-4 ns-py-5 ns-relative">
                <div class="ns-pb-10 ns-grid ns-gap-y-2">
                    <template v-for="(policy) in policies">
                        <Policy :disabled="disabled" :policy="policy" v-model="value" />
                    </template>
                </div>

                <span
                    class="ns-absolute ns-select-none ns-cursor-pointer ns-bottom-0 ns-left-0 ns-right-0 ns-text-center ns-w-full ns-py-4 hover:ns-font-bold ns-transition-all ns-delay-75 ns-duration-150 ns-ease-linear"
                    @click="showAll = !showAll" v-if="resource.policies.length > 4">
                    <span v-if="!showAll">
                        Show ({{ resource.policies.length - 4 }} more)
                    </span>
                    <span v-else>
                        Show less ({{ resource.policies.length - 4 }} more)
                    </span>
                </span>
            </div>
            <!-- </div> -->
        </Card>
    </div>
</template>
