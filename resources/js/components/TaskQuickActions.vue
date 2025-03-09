<script setup lang="ts">
import { visitModal } from '@inertiaui/modal-vue'
import {MoreVertical} from 'lucide-vue-next';
import {
  DropdownMenu,
  DropdownMenuItem,
  DropdownMenuContent,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';

const openMoveToList = (taskId) => {
  visitModal(route('tasks.move', taskId), {
    config: {
      slideover: true,
    },
    onClose: () => {
      // Refresh task data if needed
    }
  })
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger asChild>
      <Button variant="ghost" size="icon">
        <MoreVertical class="h-4 w-4" />
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent>
      <DropdownMenuItem @click="openMoveToList(task.id)">
        Move to List
      </DropdownMenuItem>
      <!-- Other actions -->
    </DropdownMenuContent>
  </DropdownMenu>
</template>
