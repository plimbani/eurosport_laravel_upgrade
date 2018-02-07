<template>
  <tinymce :id="id" :toolbar1="editorToolbar1" :plugins="plugins" :other_options="other_options" v-model="content"></tinymce>
</template>

<script>
  import tinymce from 'vue-tinymce-editor';
  export default {
    props : {
      id: {
        type: String,
        default: 'editor'
      },
      value: {
        type: String,
        default : ''
      },
    },
    data() {
      return {
        content: this.value,
        editorToolbar1 : 'formatselect | bold italic strikethrough | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat',
        plugins: ['advlist autolink lists link image charmap print preview hr anchor pagebreak','searchreplace wordcount visualblocks visualchars code fullscreen','insertdatetime media nonbreaking save table contextmenu directionality','template'],
        other_options: {
          branding: false,
          height: 300,
        },
      };
    },
    components: {
      tinymce,
    },
    mounted () {
    },
  };
</script>