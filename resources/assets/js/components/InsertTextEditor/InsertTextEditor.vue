<template>
  <div>
    <tinymce :id="id" :data-vv-name="id" :data-vv-as="validationFieldName" :toolbar1="editorToolbar1" :plugins="plugins" :other_options="other_options" v-model="content" v-validate="pageContentValidation" :class="{'is-danger': errors.has('name') }"></tinymce>
    <span class="help is-danger" v-show="errors.has(id)">{{ errors.first(id) }}</span>
  </div>
</template>

<script>
  import tinymce from './TinymceVue.vue';
  export default {
    inject: ['$validator'],
    props : {
      id: {
        type: String,
        default: 'editor'
      },
      value: {
        type: String,
        default : ''
      },
      pageContentValidation: {
        type: Object,
        default: function () { return {} },
      },
      validationFieldName: {
        type: String,
        default : ''
      }
    },
    created() {
      this.$root.$on('getEditorValue', this.getEditorValue);
      this.$root.$on('blankEditorValue', this.blankEditorValue);
    },
    beforeCreate: function() {
      // Remove custom event listener
      // this.$root.$off('getEditorValue');
      // this.$root.$off('blankEditorValue');
    },
    data() {
      return {
        content: this.value,
        editorToolbar1 : 'formatselect | bold italic strikethrough | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat',
        plugins: ['advlist autolink lists link image charmap print preview hr anchor pagebreak','searchreplace wordcount visualblocks visualchars code fullscreen','insertdatetime media nonbreaking save table contextmenu directionality','template'],
        other_options: {
          branding: false,
          height: 300,
          body_class: 'tiny-mce-content-body',
          content_css: ['https://fonts.googleapis.com/css?family=Lato', '/assets/css/tiny-mce.css'],
        },
      };
    },
    components: {
      tinymce,
    },
    mounted () {
    },
    watch: {
      value: function(value) {
        this.content = value;
      }
    },
    methods: {
      getEditorValue() {
        this.$emit('setEditorValue', this.content);
      },
      blankEditorValue(id) {
        if(this.id == id) {
          this.content = '';
        }
      }
    },
  };
</script>
