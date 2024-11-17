<template>
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      height="24"
      viewBox="0 0 24 24"
      :fill="isSeen ? 'blue' : 'none'"  
      :stroke="isSeen ? 'blue' : '#ccc'" 
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    >
      <polyline points="20 6 9 17 4 12"></polyline>
      <polyline points="16 6 9 13 7 11"></polyline>
    </svg>
</template>

<script>
export default {
  props: {
    isSeen: {
      type: Boolean,
      required: true, 
    },
  },
};
</script>
