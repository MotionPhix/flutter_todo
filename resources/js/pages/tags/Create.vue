<script setup lang="ts">
import { COLOR_PRESETS } from '@/lib/constants';
import { useForm } from '@inertiajs/vue3';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from '@/components/ui/select';
import BaseModal from '@/components/BaseModal.vue';

const props = defineProps<{
  open: boolean
}>();

const emit = defineEmits(['update:open', 'created']);

const form = useForm({
  id: props.initialData?.id,
  name: props.initialData?.name ?? '',
  type: props.initialData?.type ?? 'label',
  color: props.initialData?.color ?? COLOR_PRESETS[10].value // Default blue
});

const types = [
  { value: 'priority', label: 'Priority' },
  { value: 'status', label: 'Status' },
  { value: 'category', label: 'Category' },
  { value: 'label', label: 'Label' }
];

const onSubmit = () => {
  form.post(route('tags.store'), {
    onSuccess: () => {
      form.reset();
      emit('created');
    }
  });
};
</script>

<template>
  <BaseModal
    title="Create New Label">
    <Form @submit="onSubmit" class="space-y-4">
      <FormField
        v-model="form.name"
        name="name"
        :error="form.errors.name"
      >
        <FormLabel>Label Name</FormLabel>
        <FormControl>
          <Input v-model="form.name" placeholder="Enter label name" />
        </FormControl>
        <FormMessage />
      </FormField>

      <FormField
        v-model="form.type"
        name="type"
        :error="form.errors.type"
      >
        <FormLabel>Type</FormLabel>
        <Select v-model="form.type">
          <FormControl>
            <SelectTrigger>
              <SelectValue placeholder="Select type" />
            </SelectTrigger>
          </FormControl>
          <SelectContent>
            <SelectItem
              v-for="type in types"
              :key="type.value"
              :value="type.value"
            >
              {{ type.label }}
            </SelectItem>
          </SelectContent>
        </Select>
        <FormMessage />
      </FormField>

      <FormField
        v-model="form.color"
        name="color"
        :error="form.errors.color"
      >
        <FormLabel>Color</FormLabel>
        <FormControl>
          <Input
            v-model="form.color"
            type="color"
          />
        </FormControl>
        <FormMessage />
      </FormField>

      <div class="flex justify-end gap-4">
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
          Create Label
        </Button>
      </div>
    </Form>
  </BaseModal>
</template>
