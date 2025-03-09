<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import BaseModal from '@/components/BaseModal.vue';

const form = useForm({
  name: ''
});

const onSubmit = () => {
  form.post(route('lists.store'), {
    onSuccess: () => {
      form.reset();
      emit('created');
    }
  });
};
</script>

<template>
  <BaseModal
    title="Create New List">
    <Form @submit="onSubmit">
      <FormField
        v-model="form.name"
        name="name"
        :error="form.errors.name">
        <FormLabel>List Name</FormLabel>
        <FormControl>
          <Input v-model="form.name" placeholder="Enter list name" />
        </FormControl>
        <FormMessage />
      </FormField>

      <div class="flex justify-end gap-4 mt-6">
        <Button
          type="button"
          variant="outline"
          @click="$emit('update:open', false)">
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
