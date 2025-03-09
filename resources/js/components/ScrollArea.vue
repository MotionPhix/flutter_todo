<script setup lang="ts">
import {
  ScrollAreaCorner,
  ScrollAreaRoot,
  ScrollAreaScrollbar,
  ScrollAreaThumb,
  ScrollAreaViewport
} from 'radix-vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: string;
}>();
</script>

<template>
  <ScrollAreaRoot :class="cn('relative overflow-hidden', props.class)">
    <ScrollAreaViewport class="h-full w-full rounded-[inherit]">
      <slot />
    </ScrollAreaViewport>

    <ScrollAreaScrollbar
      class="flex transition-colors select-none touch-none"
      orientation="vertical">
      <ScrollAreaThumb class="relative flex-1 rounded-full bg-border" />
    </ScrollAreaScrollbar>

    <ScrollAreaScrollbar
      class="flex transition-colors select-none touch-none"
      orientation="horizontal">
      <ScrollAreaThumb class="relative flex-1 rounded-full bg-border" />
    </ScrollAreaScrollbar>

    <ScrollAreaCorner />
  </ScrollAreaRoot>
</template>
