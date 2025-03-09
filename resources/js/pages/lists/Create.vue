<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage
} from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { ref } from 'vue'
import BaseModal from '@/components/BaseModal.vue';

const modal = ref()
const form = useForm({
  name: '',
  description: '',
})

const onSubmit = () => {
  form.post(route('lists.store'), {
    preserveScroll: true,
    onSuccess: () => {
      modal.value.close()
    },
  })
}
</script>

<template>
  <BaseModal
    ref="modal"
    title="Create New List"
    max-width="md">
    <Form @submit="onSubmit" class="space-y-4">
      <FormField
        v-model="form.name"
        name="name"
        :error="form.errors.name">
        <FormItem>
          <FormLabel>List Name</FormLabel>
          <FormControl>
            <Input
              v-model="form.name"
              placeholder="Enter list name"
              :disabled="form.processing"
            />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>

      <FormField
        v-model="form.description"
        name="description"
        :error="form.errors.description">
        <FormItem>
          <FormLabel>Description</FormLabel>
          <FormControl>
            <Textarea
              v-model="form.description"
              placeholder="Enter list description"
              :disabled="form.processing"
            />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>

      <div class="flex justify-end gap-2">
        <Button
          type="button"
          variant="outline"
          :disabled="form.processing"
          @click="modal.value.close()">
          Cancel
        </Button>

        <Button
          type="submit"
          :disabled="form.processing">
          Create List
        </Button>
      </div>
    </Form>
  </BaseModal>
</template>
