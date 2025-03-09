<script setup lang="ts">
import { ref, computed } from 'vue'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { Button } from '@/components/ui/button'
import { cn } from '@/lib/utils'
import { COLOR_PRESETS } from '@/lib/constants'

const props = defineProps<{
  modelValue: string
  colors?: string[]
  disabled?: boolean
}>()

const emit = defineEmits(['update:modelValue'])

const availableColors = computed(() => props.colors || COLOR_PRESETS)

const selectedColor = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const isOpen = ref(false)

const handleColorSelect = (color: { name: string, value: string }) => {
  selectedColor.value = color.value
  isOpen.value = false
}
</script>

<template>
  <Popover v-model:open="isOpen">
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :disabled="disabled"
        class="w-[4rem] px-2">
        <span class="sr-only">Pick a color</span>

        <div
          class="h-4 w-4 rounded-full border"
          :style="{ backgroundColor: selectedColor }"
        />
      </Button>
    </PopoverTrigger>

    <PopoverContent class="w-64 p-3">
      <div class="grid grid-cols-5 gap-2">
        <Button
          v-for="color in availableColors"
          :key="color"
          variant="outline"
          class="h-8 w-8 p-0"
          :class="{ 'ring-2 ring-ring ring-offset-2': selectedColor === color }"
          @click="handleColorSelect(color)">
          <span class="sr-only">{{ color }}</span>
          <div
            class="h-full w-full rounded-sm"
            :style="{ backgroundColor: color }"
          />
        </Button>
      </div>
    </PopoverContent>
  </Popover>
</template>

<style scoped>
.color-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 0.5rem;
}
</style>
