<script setup lang="ts">
import { Modal } from '@inertiaui/modal-vue'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle
} from '@/components/ui/card';
import { ref } from 'vue';

const baseModalRef = ref()

withDefaults(
  defineProps<{
    manualClose?: boolean
    placement?: 'center' | 'top' | 'bottom'
    hasCloseButton?: boolean
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl'
    padding?: string
    title?: string
    description?: string
  }>(), {
    maxWidth: 'md',
    placement: 'center',
    manualClose: true,
  }
)

function close() {
  baseModalRef.value.close()
}

defineExpose({
  close,
});
</script>

<template>
  <Modal
    ref="baseModalRef"
    :max-width="maxWidth"
    :position="placement"
    :paddingClasses="padding"
    :close-explicitly="manualClose"
    :close-button="hasCloseButton"
    panel-classes="dark:text-muted-foreground max-h-[80svh] overflow-y-auto scrollbar-none scroll-smooth">
    <Card class="border-0 shadow-none">
      
      <CardHeader v-if="title">

        <CardTitle>{{ title }}</CardTitle>

        <CardDescription v-if="description">
          {{ description }}
        </CardDescription>

      </CardHeader>

      <CardContent>
        <slot />
      </CardContent>

    </Card>
  </Modal>
</template>
