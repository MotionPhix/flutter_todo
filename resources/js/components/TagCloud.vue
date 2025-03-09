<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

interface Tag {
  id: number
  name: string
  type: string
  color: string
  slug: string
  weight: number
}

const props = defineProps<{
  tags: Tag[]
}>()

const maxWeight = computed(() => Math.max(...props.tags.map(t => t.weight)))
const minWeight = computed(() => Math.min(...props.tags.map(t => t.weight)))

const getTagSize = (weight: number) => {
  const minSize = 0.8
  const maxSize = 2
  const scale = (maxSize - minSize) / (maxWeight.value - minWeight.value)
  return minSize + (weight - minWeight.value) * scale
}
</script>

<template>
  <div class="flex flex-wrap gap-3 p-4">
    <Link
      v-for="tag in tags"
      :key="tag.id"
      :href="route('tags.show', [tag.type, tag.slug])"
      class="transition-all duration-200 hover:-translate-y-0.5"
      :style="{
        fontSize: `${getTagSize(tag.weight)}rem`,
        color: tag.color,
      }"
    >
      #{{ tag.name }}
    </Link>
  </div>
</template>
