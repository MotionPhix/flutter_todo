<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { CheckSquare, MoreVertical, Trash } from 'lucide-vue-next'

const props = defineProps<{
  selected: number[]
  type: 'lists' | 'labels'
}>()

const emit = defineEmits(['clear'])

const form = useForm({})

const handleDelete = () => {
  if (confirm(`Are you sure you want to delete the selected ${props.type}?`)) {
    form.delete(route(`${props.type}.bulk-delete`), {
      data: { ids: props.selected },
      onSuccess: () => emit('clear'),
    })
  }
}
</script>

<template>
  <div class="flex items-center gap-2">
    <span class="text-sm text-muted-foreground">
      {{ selected.length }} selected
    </span>

    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" size="sm">
          Actions
          <MoreVertical class="w-4 h-4 ml-2" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent>
        <DropdownMenuItem @click="handleDelete">
          <Trash class="w-4 h-4 mr-2" />
          Delete Selected
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>

    <Button
      variant="ghost"
      size="sm"
      @click="$emit('clear')"
    >
      Clear Selection
    </Button>
  </div>
</template>
