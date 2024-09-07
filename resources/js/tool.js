import FormField from "./fields/FormField.vue";
import DetailField from "./fields/DetailField.vue";
import IndexField from "./fields/IndexField.vue";
import Panel from "./components/panel.vue";

Nova.booting((app, store) => {
  app.component("FormNovaShieldPanel", Panel);
  app.component("DetailNovaShieldPanel", Panel);

  app.component("index-nova-shield", IndexField);
  app.component("detail-nova-shield", DetailField);
  app.component("form-nova-shield", FormField);
});
