<script setup lang="ts">
import { computed } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar'
import { Clock, Users } from 'lucide-vue-next'

const props = defineProps<{
  item: any
  type: 'list' | 'task' | 'project'
}>()

const preview = computed(() => {
  switch (props.type) {
    case 'list':
      return {
        title: props.item.name,
        badge: `${props.item.tasks_count} tasks`,
        meta: props.item.description
      }
    case 'task':
      return {
        title: props.item.title,
        badge: props.item.status,
        meta: `Due ${new Date(props.item.due_date).toLocaleDateString()}`
      }
    case 'project':
      return {
        title: props.item.name,
        badge: `${props.item.members_count} members`,
        meta: props.item.description
      }
    default:
      return { title: '', badge: '', meta: '' }
  }
})
</script>

<template>
  <Card class="w-64 shadow-lg">
    <CardContent class="p-3">
      <div class="flex items-start gap-3">
        <div class="flex-1 min-w-0">
          <p class="font-medium truncate">{{ preview.title }}</p>
          <p v-if="preview.meta" class="text-sm text-muted-foreground truncate">
            {{ preview.meta }}
          </p>
        </div>
        <Badge>{{ preview.badge }}</Badge>
      </div>
    </CardContent>
  </Card>
</template>
