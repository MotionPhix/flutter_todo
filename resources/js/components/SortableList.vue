<script setup lang="ts">
import { computed } from 'vue'
import draggable from 'vuedraggable'
import { TransitionGroup } from 'vue'

interface Props {
  modelValue: any[]
  itemKey?: string
  handle?: boolean
  group?: string | { name: string, pull?: boolean | string[], put?: boolean | string[] }
  disabled?: boolean
  animation?: number
  ghostClass?: string
  dragClass?: string
  chosenClass?: string
  fallbackTolerance?: number
  ignoreTransform?: boolean
  delay?: number
  delayOnTouchOnly?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  itemKey: 'id',
  handle: false,
  disabled: false,
  animation: 300,
  ghostClass: 'draggable-ghost',
  dragClass: 'draggable-drag',
  chosenClass: 'draggable-chosen',
  fallbackTolerance: 5,
  ignoreTransform: false,
  delay: 0,
  delayOnTouchOnly: false
})

const emit = defineEmits<{
  'update:modelValue': [value: any[]],
  'start': [event: any],
  'end': [event: any],
  'add': [event: any],
  'remove': [event: any],
  'move': [event: any]
}>()

const items = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const handleStart = (event: any) => {
  emit('start', event)
}

const handleEnd = (event: any) => {
  emit('end', event)
}

const handleAdd = (event: any) => {
  emit('add', event)
}

const handleRemove = (event: any) => {
  emit('remove', event)
}

const handleMove = (event: any) => {
  emit('move', event)
}

// Generate a unique key for the draggable component
const draggableKey = computed(() =>
  props.modelValue.map(item => item[props.itemKey]).join('-')
)
</script>

<template>
  <TransitionGroup
    tag="div"
    name="draggable"
    class="draggable-transition-group"
  >
    <draggable
      :key="draggableKey"
      v-model="items"
      :item-key="itemKey"
      :handle="handle ? '.handle' : false"
      :group="group"
      :disabled="disabled"
      :animation="animation"
      :ghost-class="ghostClass"
      :drag-class="dragClass"
      :chosen-class="chosenClass"
      :fallback-tolerance="fallbackTolerance"
      :ignore-transform="ignoreTransform"
      :delay="delay"
      :delay-on-touch-only="delayOnTouchOnly"
      @start="handleStart"
      @end="handleEnd"
      @add="handleAdd"
      @remove="handleRemove"
      @move="handleMove"
    >
      <template #item="{ element, index }">
        <div
          :key="element[itemKey]"
          :data-index="index"
          class="draggable-move"
        >
          <slot
            :item="element"
            :index="index"
          />
        </div>
      </template>
    </draggable>
  </TransitionGroup>
</template>

<style>
.draggable-move {
  transition: transform 0.3s ease;
}

.draggable-ghost {
  opacity: 0.5;
  @apply bg-accent/50;
}

.draggable-drag {
  cursor: grabbing;
  @apply shadow-lg z-50;
}

.draggable-chosen {
  @apply ring-2 ring-primary ring-offset-2;
}

/* Add transition classes */
.draggable-enter-active,
.draggable-leave-active {
  transition: all 0.3s ease;
}

.draggable-enter-from,
.draggable-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.draggable-move {
  transition: transform 0.3s ease;
}
</style>
