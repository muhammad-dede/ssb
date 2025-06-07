<script setup>
defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    className: {
        type: [String, Array, Object],
        default: undefined,
    },
});
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 512 512"
        :class="props.className"
        v-bind="$attrs"
    >
        <path
            fill="currentColor"
            d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm78.5 144.1l41.1 45.1-9.6 62.8-61.4 15.3-48.5-38.3-11.3-62.3 40.6-46.2 49.1 23.6zM102.8 180.3l41.1-45.1 49.1-23.6 40.6 46.2-11.3 62.3-48.5 38.3-61.4-15.3-9.6-62.8zm-3.5 153.4l11.7-61.5 52.8-25.1 52.8 25.1 11.7 61.5-41.1 45.1H140.4l-41.1-45.1zm217.2 96.2l-61.5 11.7-61.5-11.7-25.1-52.8 25.1-52.8 61.5-11.7 61.5 11.7 25.1 52.8-25.1 52.8zm94.7-96.2l-41.1 45.1h-52.3l-41.1-45.1 11.7-61.5 52.8-25.1 52.8 25.1 11.7 61.5z"
        />
    </svg>
</template>
