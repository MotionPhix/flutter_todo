<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { TagList } from './partials/TagList.vue'
import { CreateTagForm } from './partials/CreateTagForm.vue'

defineProps<{
  tagsByType: Record<string, Array<{
    id: number
    name: string
    slug: string
    count: number
  }>>
}>()
</script>

<template>
  <div class="container py-6">
    <Head title="Tags" />

    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Tags</h1>

      <Dialog>
        <DialogTrigger asChild>
          <Button>New Tag</Button>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Create New Tag</DialogTitle>
          </DialogHeader>
          <CreateTagForm />
        </DialogContent>
      </Dialog>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
      <div
        v-for="(tags, type) in tagsByType"
        :key="type"
        class="space-y-4">
        <h2 class="text-lg font-medium capitalize">{{ type }}</h2>
        <TagList :tags="tags" :type="type" />
      </div>
    </div>
  </div>
</template>
