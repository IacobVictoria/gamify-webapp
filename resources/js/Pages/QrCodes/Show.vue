<template>
<div>
    {{ product.id }}
</div>
<h1>{{ product.name }}</h1>

<div v-for="qrCode in qrCodes" :key="qrCode.id">
      <img :src="qrCode.image_url" :alt="'QR code for ' + product.name" />
    </div>
</template>
<script>
export default{
    name: 'QrCodes.Show',

    props: {
        qrCodes: Array,
        product: Object
    }
}
</script>