<script setup lang="ts">
import { computed, ref } from 'vue';
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList
} from '@/components/ui/command'
import { Badge } from '@/components/ui/badge'
import { X } from 'lucide-vue-next'

const props = defineProps<{
  modelValue: string[]
  existingTags?: string[]
}>()

const emit = defineEmits(['update:modelValue'])

const searchQuery = ref('')
const showSuggestions = ref(false)

const suggestions = computed(() => {
  if (!searchQuery.value) return props.existingTags || []

  return (props.existingTags || []).filter(tag =>
    tag.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const addTag = (tag: string) => {
  if (!props.modelValue.includes(tag)) {
    emit('update:modelValue', [...props.modelValue, tag])
  }
  searchQuery.value = ''
  showSuggestions.value = false
}

const removeTag = (tagToRemove: string) => {
  emit('update:modelValue', props.modelValue.filter(tag => tag !== tagToRemove))
}
</script>

<template>
  <div class="space-y-2">
    <div class="flex flex-wrap gap-2">
      <Badge
        v-for="tag in modelValue"
        :key="tag"
        variant="secondary"
        class="flex items-center gap-1">
        {{ tag }}
        <button
          type="button"
          @click="removeTag(tag)"
          class="h-3 w-3 rounded-full hover:bg-accent">
          <X class="h-3 w-3" />
        </button>
      </Badge>
    </div>

    <div class="relative">
      <Command class="border">
        <CommandInput
          v-model="searchQuery"
          placeholder="Add tags..."
          @focus="showSuggestions = true"
        />
        <CommandList v-if="showSuggestions">
          <CommandEmpty>No tags found.</CommandEmpty>
          <CommandGroup>
            <CommandItem
              v-for="tag in suggestions"
              :key="tag"
              :value="tag"
              @select="addTag(tag)">
              {{ tag }}
            </CommandItem>
          </CommandGroup>
        </CommandList>
      </Command>
    </div>
  </div>
</template>
