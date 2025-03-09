<script setup lang="ts">
import { useSearchStore } from '@/stores/useSearchStore'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Button } from '@/components/ui/button'
import DateRangePicker from '@/components/DateRangePicker.vue'
import MultiSelect from '@/components/MultiSelect.vue'
import { computed, toRef } from 'vue'
import type { PropType } from 'vue'

interface DateRange {
  start: Date | null
  end: Date | null
}

// Define proper prop types
const props = defineProps({
  open: {
    type: Boolean,
    required: true
  },
  modelValue: {
    type: Object as PropType<Record<string, any>>,
    default: () => ({})
  }
})

const emit = defineEmits<{
  'update:open': [value: boolean]
  'update:modelValue': [value: Record<string, any>]
  'search': [filters: Record<string, any>]
}>()

const searchStore = useSearchStore()
const { filters } = searchStore

// Convert open prop to ref for v-model binding
const isOpen = toRef(props, 'open')

const typeOptions = [
  { value: 'all', label: 'All' },
  { value: 'tasks', label: 'Tasks' },
  { value: 'projects', label: 'Projects' },
  { value: 'notes', label: 'Notes' },
  { value: 'tags', label: 'Tags' },
]

const statusOptions = [
  { value: 'todo', label: 'To Do' },
  { value: 'in_progress', label: 'In Progress' },
  { value: 'done', label: 'Done' },
  { value: 'cancelled', label: 'Cancelled' },
]

const priorityOptions = [
  { value: 'low', label: 'Low' },
  { value: 'medium', label: 'Medium' },
  { value: 'high', label: 'High' },
  { value: 'urgent', label: 'Urgent' },
]

const handleSearch = () => {
  emit('search', filters)
  emit('update:open', false)
}

const clearFilters = () => {
  filters.type = 'all'
  filters.date.from = null
  filters.date.to = null
  filters.status = []
  filters.priority = []
  filters.assignee = null
  filters.list = null
  filters.tags = []
}

const dateRange = computed<DateRange>({
  get: () => ({
    start: filters.date.from,
    end: filters.date.to
  }),
  set: (value: DateRange) => {
    if (value) {
      filters.date.from = value.start
      filters.date.to = value.end
    } else {
      filters.date.from = null
      filters.date.to = null
    }
  }
})
</script>

<template>
  <Dialog :open="isOpen" @update:open="$emit('update:open', $event)">
    <DialogContent class="sm:max-w-[600px]">
      <DialogHeader>
        <DialogTitle>Advanced Search</DialogTitle>
      </DialogHeader>

      <div class="grid gap-4 py-4">
        <div class="grid grid-cols-2 gap-4">
          <!-- Type Select -->
          <div class="space-y-2">
            <label class="text-sm font-medium">Type</label>
            <Select v-model="filters.type">
              <SelectTrigger>
                <SelectValue placeholder="Select type" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="option in typeOptions"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Date Range -->
          <div class="space-y-2">
            <label class="text-sm font-medium">Date Range</label>
            <DateRangePicker
              v-model="dateRange"
              placeholder="Select date range"
            />
          </div>
        </div>

        <!-- Status Multi-select -->
        <div class="space-y-2">
          <label class="text-sm font-medium">Status</label>
          <MultiSelect
            v-model="filters.status"
            :options="statusOptions"
            placeholder="Select status"
          />
        </div>

        <!-- Priority Multi-select -->
        <div class="space-y-2">
          <label class="text-sm font-medium">Priority</label>
          <MultiSelect
            v-model="filters.priority"
            :options="priorityOptions"
            placeholder="Select priority"
          />
        </div>

        <!-- Tags Multi-select -->
        <div class="space-y-2">
          <label class="text-sm font-medium">Tags</label>
          <MultiSelect
            v-model="filters.tags"
            :options="$page.props.labels"
            option-label="name"
            option-value="id"
            placeholder="Select tags"
          />
        </div>
      </div>

      <div class="flex justify-between">
        <Button
          variant="outline"
          @click="clearFilters"
        >
          Clear Filters
        </Button>

        <div class="flex gap-2">
          <Button
            variant="outline"
            @click="$emit('update:open', false)"
          >
            Cancel
          </Button>

          <Button @click="handleSearch">
            Search
          </Button>
        </div>
      </div>
    </DialogContent>
  </Dialog>
</template>
