<template>
  <section class="c-input__line">
    <input class="c-input__text" @change="setImage" ref="file" id="profile_icon" type="file" name="profile_icon" value="old.profile_icon"></br>
    <div class="c-img__container--midi">
      <img v-if="data.image" class="js-preview c-img__img" :src="data.image" alt="">
      <img v-else-if="this.icon" class="js-preview c-img__img" :src="src" alt="">
      <img v-else class="js-preview c-img__img" :src="this.default" alt="">
    </div>
  </section>
</template>

<script>
export default {
  props:['icon', 'src', 'default'],
  data () {
    return {
      data: {
        image: "",
        name: "",
      }

    }
  },
  methods: {
    setImage(e){
      const files = this.$refs.file;
        const fileImg = files.files[0];
        if (fileImg.type.startsWith("image/")) {
          this.data.image = window.URL.createObjectURL(fileImg);
          this.data.name = fileImg.name;
          this.data.type = fileImg.type;
        }
    }
  },
  mounted () {

  }
}

</script>
