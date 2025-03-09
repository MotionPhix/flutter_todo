<script setup lang="ts">
import { ref, computed } from 'vue'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
} from '@/components/ui/command'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { Badge } from '@/components/ui/badge'
import { cn } from '@/lib/utils'
import { Check, X, ChevronsUpDown } from 'lucide-vue-next'

interface Option {
  value: string | number
  label: string
  [key: string]: any
}

const props = defineProps<{
  modelValue: (string | number)[]
  options: Option[]
  placeholder?: string
  emptyMessage?: string
  optionLabel?: string
  optionValue?: string
}>()

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const search = ref('')

const selectedOptions = computed(() => {
  const labelKey = props.optionLabel || 'label'
  const valueKey = props.optionValue || 'value'

  return props.options.filter(option =>
    props.modelValue.includes(option[valueKey])
  )
})

const filteredOptions = computed(() => {
  const labelKey = props.optionLabel || 'label'
  return props.options.filter(option =>
    option[labelKey].toLowerCase().includes(search.value.toLowerCase())
  )
})

const toggleOption = (option: Option) => {
  const valueKey = props.optionValue || 'value'
  const value = option[valueKey]
  const selected = props.modelValue.includes(value)

  if (selected) {
    emit('update:modelValue', props.modelValue.filter(v => v !== value))
  } else {
    emit('update:modelValue', [...props.modelValue, value])
  }
}

const removeOption = (optionToRemove: Option) => {
  const valueKey = props.optionValue || 'value'
  emit('update:modelValue',
    props.modelValue.filter(value => value !== optionToRemove[valueKey])
  )
}

const clearAll = () => {
  emit('update:modelValue', [])
}
</script>

<template>
  <Popover v-model:open="open" class="w-full">
    <PopoverTrigger as-child>
      <div
        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
        :class="{ 'h-auto': selectedOptions.length > 0 }"
      >
        <div class="flex flex-wrap gap-1">
          <div v-if="selectedOptions.length === 0" class="text-muted-foreground">
            {{ placeholder || 'Select options...' }}
          </div>
          <Badge
            v-for="option in selectedOptions"
            :key="option[optionValue || 'value']"
            variant="secondary"
            class="mr-1 mb-1"
          >
            {{ option[optionLabel || 'label'] }}
            <button
              class="ml-1 ring-offset-background rounded-full outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
              @click.stop="removeOption(option)"
            >
              <X class="h-3 w-3" />
            </button>
          </Badge>
        </div>
        <ChevronsUpDown class="h-4 w-4 opacity-50" />
      </div>
    </PopoverTrigger>
    <PopoverContent class="w-[--radix-popover-trigger-width] p-0">
      <Command>
        <CommandInput
          v-model="search"
          placeholder="Search options..."
        />
        <CommandEmpty>
          {{ emptyMessage || 'No options found.' }}
        </CommandEmpty>
        <CommandGroup class="max-h-[300px] overflow-auto">
          <CommandItem
            v-for="option in filteredOptions"
            :key="option[optionValue || 'value']"
            :value="option[optionLabel || 'label']"
            @select="toggleOption(option)"
          >
            <Check
              :class="cn(
                'mr-2 h-4 w-4',
                modelValue.includes(option[optionValue || 'value'])
                  ? 'opacity-100'
                  : 'opacity-0'
              )"
            />
            {{ option[optionLabel || 'label'] }}
          </CommandItem>
        </CommandGroup>
      </Command>
      <div class="p-2 border-t">
        <button
          class="w-full text-xs text-muted-foreground hover:text-foreground"
          @click="clearAll"
        >
          Clear all
        </button>
      </div>
    </PopoverContent>
  </Popover>
</template>
