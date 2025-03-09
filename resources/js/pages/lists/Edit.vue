<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'

const props = defineProps<{
  open: boolean
  list: {
    id: number
    name: string
    description: string | null
  }
}>()

const emit = defineEmits(['update:open', 'updated'])

const form = useForm({
  name: props.list.name,
  description: props.list.description,
})

const onSubmit = () => {
  form.put(route('lists.update', props.list.id), {
    onSuccess: () => emit('updated'),
  })
}

const onDelete = () => {
  if (confirm('Are you sure you want to delete this list? Tasks in this list will be moved to "No List".')) {
    form.delete(route('lists.destroy', props.list.id), {
      onSuccess: () => emit('update:open', false),
    })
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Edit List</DialogTitle>
      </DialogHeader>

      <Form @submit="onSubmit" class="space-y-4">
        <FormField
          v-model="form.name"
          name="name"
          :error="form.errors.name"
        >
          <FormLabel>List Name</FormLabel>
          <FormControl>
            <Input v-model="form.name" placeholder="Enter list name" />
          </FormControl>
          <FormMessage />
        </FormField>

        <FormField
          v-model="form.description"
          name="description"
          :error="form.errors.description"
        >
          <FormLabel>Description (Optional)</FormLabel>
          <FormControl>
            <Textarea
              v-model="form.description"
              placeholder="Enter list description"
              rows="3"
            />
          </FormControl>
          <FormMessage />
        </FormField>

        <div class="flex justify-between">
          <Button
            type="button"
            variant="destructive"
            :disabled="form.processing"
            @click="onDelete"
          >
            Delete List
          </Button>
          <div class="flex gap-4">
            <Button
              type="button"
              variant="outline"
              @click="$emit('update:open', false)"
            >
              Cancel
            </Button>
            <Button
              type="submit"
              :disabled="form.processing"
            >
              Update List
            </Button>
          </div>
        </div>
      </Form>
    </DialogContent>
  </Dialog>
</template>
