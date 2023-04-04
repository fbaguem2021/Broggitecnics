<template>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="interlocutor-tab" data-bs-toggle="tab" data-bs-target="#interlocutor" type="button" role="tab" aria-controls="Interlocutor" aria-selected="true">Interlocutor</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="localitzacio-tab" data-bs-toggle="tab" data-bs-target="#localitzacio" type="button" role="tab" aria-controls="Localitzacio" aria-selected="false">Localització</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident" type="button" role="tab" aria-controls="Incident" aria-selected="false">Incident</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="interlocutor" role="tabpanel" aria-labelledby="interlocutor-tab">
            <interlocutor-form></interlocutor-form>
        </div>
        <div class="tab-pane" id="localitzacio" role="tabpanel" aria-labelledby="localitzacio-tab">WHERE DFUQ AM I</div>
        <div class="tab-pane" id="incident" role="tabpanel" aria-labelledby="incident-tab">HELP THE WATER OF THE CITY GIVES ME ANXIETY</div>
    </div>
</template>
<script>
import InterlocutorForm from './InterlocutorForm.vue';
export default {
  components: {
    InterlocutorForm
  }
}
</script>
<style scoped>
    .nav-tabs {
        background-color: #DFE2E6;
        border-bottom: 2px solid var(--primary);
        border-radius: 5px 5px 0 0;
    }
    .nav-tabs:first-child {
        padding-left: 20px;
    }
    .nav-item {
        padding-top: 20px;
        width: 200px;
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .nav-item button {
        width: 100%;
    }

    .nav-link {
        border: none;
        color: var(--success);
    }
   .nav-link.active {
        border: 2px solid var(--primary);
        border-bottom: none;
        color: black;
        height: 104%;
    }
    .tab-content {
        flex-grow: 1;
    }
    .tab-pane {
        height: 100%;
        padding: 40px;
    }
</style>