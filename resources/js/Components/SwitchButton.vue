<template>
  <div class="mode-edition-container w-max text-xl mb-4 rounded-xl">
    <span class="mode-label">Mode édition</span>
    <label class="switch-button">
      <input 
        type="checkbox" 
        :checked="modelValue" 
        @change="$emit('update:modelValue', $event.target.checked)"
      >
      <span class="slider"></span>
    </label>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: Boolean,
    required: true
  }
});

defineEmits(['update:modelValue']);
</script>

<style scoped>
.switch-button {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 28px;
}

.switch-button input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #FF7C7C;
  transition: .3s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #00BE72;
}

input:checked + .slider:before {
  transform: translateX(22px);
}

.mode-edition-container {
  display: flex;
  align-items: center;
  gap: 20px;
  background-color: white;
  padding:15px 20px;
  border-radius: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.mode-label {
  font-size: 18px;
  color: #333;
}
</style>
