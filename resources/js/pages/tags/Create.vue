<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
  FormDescription
} from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import BaseModal from '@/components/BaseModal.vue';
import ColorPicker from '@/components/ColorPicker.vue';
import { COLOR_PRESETS } from '@/lib/constants'

const modal = ref()
const form = useForm({
  name: '',
  type: 'label',
  color: COLOR_PRESETS[11].value,
})

const types = [
  { value: 'label', label: 'Label' },
  { value: 'category', label: 'Category' },
]

const onSubmit = () => {
  form.post(route('tags.store'), {
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
    title="Create New Label"
    max-width="md">
    <Form @submit="onSubmit" class="space-y-4">
      <FormField
        v-model="form.name"
        name="name"
        :error="form.errors.name">
        <FormItem>
          <FormLabel>Label Name</FormLabel>
          <FormControl>
            <Input
              v-model="form.name"
              placeholder="Enter label name"
              :disabled="form.processing"
            />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>

      <FormField
        v-model="form.type"
        name="type"
        :error="form.errors.type">
        <FormItem>
          <FormLabel>Type</FormLabel>
          <Select v-model="form.type" :disabled="form.processing">
            <FormControl>
              <SelectTrigger>
                <SelectValue placeholder="Select type" />
              </SelectTrigger>
            </FormControl>
            <SelectContent>
              <SelectItem
                v-for="type in types"
                :key="type.value"
                :value="type.value">
                {{ type.label }}
              </SelectItem>
            </SelectContent>
          </Select>
          <FormDescription>
            Labels are used for tagging items, while categories help organize them hierarchically.
          </FormDescription>
          <FormMessage />
        </FormItem>
      </FormField>

      <FormField
        v-model="form.color"
        name="color"
        :error="form.errors.color">
        <FormItem>
          <FormLabel>Color</FormLabel>
          <FormControl>
            <ColorPicker
              v-model="form.color"
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
          Create Label
        </Button>
      </div>
    </Form>
  </BaseModal>
</template>
