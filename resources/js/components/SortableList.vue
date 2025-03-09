<script setup lang="ts">
import { computed } from 'vue'
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
} from '@dnd-kit/core'
import {
  arrayMove,
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
} from '@dnd-kit/sortable'

const props = defineProps<{
  items: any[]
}>()

const emit = defineEmits(['reorder'])

const sensors = useSensors(
  useSensor(PointerSensor),
  useSensor(KeyboardSensor, {
    coordinateGetter: sortableKeyboardCoordinates,
  })
)

const handleDragEnd = (event) => {
  const { active, over } = event

  if (active.id !== over.id) {
    const oldIndex = props.items.findIndex(item => item.id === active.id)
    const newIndex = props.items.findIndex(item => item.id === over.id)
    emit('reorder', arrayMove(props.items, oldIndex, newIndex))
  }
}
</script>

<template>
  <DndContext
    :sensors="sensors"
    collisionDetection={closestCenter}
    @dragend="handleDragEnd"
  >
    <SortableContext
      :items="items"
      strategy={verticalListSortingStrategy}
    >
      <slot />
    </SortableContext>
  </DndContext>
</template>
