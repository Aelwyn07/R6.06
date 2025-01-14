<script setup lang="ts">
import { defineProps } from 'vue';
import { ProvisionnalCalendarItem } from './ProvisionnalCalendarSlot';
import { ProvisionnalCalendarSlot } from './ProvisionnalCalendarSlot';

defineProps<{
    item: ProvisionnalCalendarItem,
}>();
</script>