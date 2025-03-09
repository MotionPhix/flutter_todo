<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import EmptyState from '@/components/EmptyState.vue';
import { Button } from '@/components/ui/button';
import NoteCard from '@/components/NoteCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

defineProps<{
  notes: {
    data: Array<{
      id: number
      title: string
      content: string
      visibility: 'public' | 'private' | 'shared'
      created_at: string
      user: {
        id: number
        name: string
        avatar: string
      }
    }>
    meta: {
      current_page: number
      last_page: number
    }
  }
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Private notes',
    href: '/settings/notes'
  }
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Private Notes" />

    <SettingsLayout>

      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-semibold tracking-tight">Private Notes</h2>
          <Button>New Note</Button>
        </div>

        <div v-if="notes.data.length" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <NoteCard
            v-for="note in notes.data"
            :key="note.id"
            :note="note"
          />
        </div>

        <EmptyState
          v-else
          title="No private notes"
          description="You haven't created any private notes yet."
        />
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
