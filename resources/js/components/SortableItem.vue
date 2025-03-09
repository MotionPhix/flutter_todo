<script setup lang="ts">
import { useSortable } from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'

const props = defineProps<{
  id: number | string
}>()

const {
  attributes,
  listeners,
  setNodeRef,
  transform,
  transition,
} = useSortable({ id: props.id })

const style = computed(() => ({
  transform: CSS.Transform.toString(transform),
  transition,
}))
</script>

<template>
  <div
    ref="setNodeRef"
    :style="style"
    v-bind="attributes"
    v-on="listeners"
  >
    <slot />
  </div>
</template>
