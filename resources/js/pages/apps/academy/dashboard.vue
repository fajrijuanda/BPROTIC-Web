<script setup>
import AcademyAssignmentProgress from '@/views/apps/academy/AcademyAssignmentProgress.vue'
import AcademyCardPopularInstructors from '@/views/apps/academy/AcademyCardPopularInstructors.vue'
import AcademyCardTopCourses from '@/views/apps/academy/AcademyCardTopCourses.vue'
import AcademyCourseTable from '@/views/apps/academy/AcademyCourseTable.vue'
import AcademyTopicYouAreInterested from '@/views/apps/academy/AcademyTopicYouAreInterested.vue'
import AcademyUpcomingWebinar from '@/views/apps/academy/AcademyUpcomingWebinar.vue'
import customCheck from '@images/svg/Check.svg'
import customLaptop from '@images/svg/laptop.svg'
import customLightbulb from '@images/svg/lightbulb.svg'
import FullCalendar from '@fullcalendar/vue3'
import {
  blankEvent,
  useCalendar,
} from '@/views/apps/calendar/useCalendar'
import { useCalendarStore } from '@/views/apps/calendar/useCalendarStore'

// Components
import CalendarEventHandler from '@/views/apps/calendar/CalendarEventHandler.vue'
const donutChartColors = {
  donut: {
    series1: '#22A95E',
    series2: '#24B364',
    series3: '#56CA00',
    series4: '#53D28C',
    series5: '#7EDDA9',
    series6: '#A9E9C5',
  },
}

const timeSpendingChartConfig = {
  chart: {
    height: 157,
    width: 130,
    parentHeightOffset: 0,
    type: 'donut',
  },
  labels: [
    '36h',
    '56h',
    '16h',
    '32h',
    '56h',
    '16h',
  ],
  colors: [
    donutChartColors.donut.series1,
    donutChartColors.donut.series2,
    donutChartColors.donut.series3,
    donutChartColors.donut.series4,
    donutChartColors.donut.series5,
    donutChartColors.donut.series6,
  ],
  stroke: { width: 0 },
  dataLabels: {
    enabled: false,
    formatter(val) {
      return `${Number.parseInt(val)}%`
    },
  },
  legend: { show: false },
  tooltip: { theme: false },
  grid: { padding: { top: 0 } },
  plotOptions: {
    pie: {
      donut: {
        size: '75%',
        labels: {
          show: true,
          value: {
            fontSize: '1.125rem',
            color: 'rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity))',
            fontWeight: 500,
            offsetY: -15,
            formatter(val) {
              return `${Number.parseInt(val)}%`
            },
          },
          name: { offsetY: 20 },
          total: {
            show: true,
            fontSize: '15px',
            label: 'Total',
            color: 'rgba(var(--v-theme-on-background), var(--v-disabled-opacity))',
            formatter() {
              return '231h'
            },
          },
        },
      },
    },
  },
}

const timeSpendingChartSeries = [
  23,
  35,
  10,
  20,
  35,
  23,
]

const store = useCalendarStore()

// 👉 Event
const event = ref(structuredClone(blankEvent))
const isEventHandlerSidebarActive = ref(false)

watch(isEventHandlerSidebarActive, val => {
  if (!val)
    event.value = structuredClone(blankEvent)
})

const { isLeftSidebarOpen } = useResponsiveLeftSidebar()

// 👉 useCalendar
const { refCalendar, calendarOptions, addEvent, updateEvent, removeEvent, jumpToDate } = useCalendar(event, isEventHandlerSidebarActive, isLeftSidebarOpen)

// SECTION Sidebar

// 👉 Check all
const checkAll = computed({

  /*GET: Return boolean `true` => if length of options matches length of selected filters => Length matches when all events are selected
SET: If value is `true` => then add all available options in selected filters => Select All
Else if => all filters are selected (by checking length of both array) => Empty Selected array  => Deselect All
*/
  get: () => store.selectedCalendars.length === store.availableCalendars.length,
  set: val => {
    if (val)
      store.selectedCalendars = store.availableCalendars.map(i => i.label)
    else if (store.selectedCalendars.length === store.availableCalendars.length)
      store.selectedCalendars = []
  },
})

const jumpToDateFn = date => {
  jumpToDate(date)
}

const userData = useCookie('userData')

</script>

<template>
  <div>
    <VRow class="py-6">
      <!-- 👉 Welcome -->
      <VCol cols="12" md="8" :class="$vuetify.display.mdAndUp ? 'border-e' : 'border-b'">
        <div class="pe-3">
          <h5 class="text-h5 mb-2">
            Welcome back,
             <span class="text-h4">  {{ userData.profile.first_name + " " + userData.profile.last_name }} </span>
          </h5>

          <div class="text-wrap text-body-1" style="max-inline-size: 360px;">
            Your progress this week is Awesome. let's keep it up
            and get a lot of points reward!
          </div>

          <div class="d-flex justify-space-between flex-wrap gap-4 flex-column flex-md-row mt-4">
            <div v-for="{ title, value, icon, color } in [
              { title: 'Hours Spent', value: '34h', icon: customLaptop, color: 'primary' },
              { title: 'Test Results', value: '82%', icon: customLightbulb, color: 'info' },
              { title: 'Course Completed', value: '14', icon: customCheck, color: 'warning' },
            ]" :key="title">
              <div class="d-flex align-center">
                <VAvatar variant="tonal" :color="color" rounded size="54" class="text-primary me-4">
                  <VIcon :icon="icon" size="38" />
                </VAvatar>
                <div>
                  <h6 class="text-h6 text-medium-emphasis">
                    {{ title }}
                  </h6>
                  <h4 class="text-h4" :class="`text-${color}`">
                    {{ value }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </VCol>

      <!-- 👉 Time Spending -->
      <VCol cols="12" md="4">
        <div class="d-flex justify-space-between align-center">
          <div class="d-flex flex-column ps-3">
            <h5 class="text-h5 mb-1 text-no-wrap">
              Time Spending
            </h5>
            <div class="text-body-1 mb-7">
              Weekly Report
            </div>
            <h4 class="text-h4 mb-2">
              231<span class="text-medium-emphasis">h</span> 14<span class="text-medium-emphasis">m</span>
            </h4>
            <div>
              <VChip color="success" label size="small">
                +18.4%
              </VChip>
            </div>
          </div>
          <div>
            <VueApexCharts type="donut" height="150" width="150" :options="timeSpendingChartConfig"
              :series="timeSpendingChartSeries" />
          </div>
        </div>
      </VCol>
    </VRow>

    <VRow>
      <VCol>
        <VCard>
          <!-- `z-index: 0` Allows overlapping vertical nav on calendar -->
          <VLayout style="z-index: 0;">
            <!-- 👉 Navigation drawer -->
            <VNavigationDrawer v-model="isLeftSidebarOpen" width="292" absolute touchless location="start"
              class="calendar-add-event-drawer" :temporary="$vuetify.display.mdAndDown">
              <div style="margin: 1.5rem;">
                <VBtn block prepend-icon="tabler-plus" @click="isEventHandlerSidebarActive = true">
                  Add event
                </VBtn>
              </div>

              <VDivider />

              <div class="d-flex align-center justify-center pa-2">
                <AppDateTimePicker :model-value="new Date().toJSON().slice(0, 10)" :config="{ inline: true }"
                  class="calendar-date-picker" @update:model-value="jumpToDateFn" />
              </div>

              <VDivider />
              <div class="pa-6">
                <h6 class="text-lg font-weight-medium mb-4">
                  Event Filters
                </h6>

                <div class="d-flex flex-column calendars-checkbox">
                  <VCheckbox v-model="checkAll" label="View all" />
                  <VCheckbox v-for="calendar in store.availableCalendars" :key="calendar.label"
                    v-model="store.selectedCalendars" :value="calendar.label" :color="calendar.color"
                    :label="calendar.label" />
                </div>
              </div>
            </VNavigationDrawer>

            <VMain>
              <VCard flat>
                <FullCalendar ref="refCalendar" :options="calendarOptions" />
              </VCard>
            </VMain>
          </VLayout>
        </VCard>
        <CalendarEventHandler v-model:isDrawerOpen="isEventHandlerSidebarActive" :event="event" @add-event="addEvent"
          @update-event="updateEvent" @remove-event="removeEvent" />
      </VCol>
    </VRow>
    <VRow class="match-height">
      <!-- 👉 Topics you are interested in -->
      <VCol cols="12" md="8">
        <!-- 👉 Topic You are Interested in -->
        <AcademyTopicYouAreInterested />
      </VCol>

      <!-- 👉 Popular Instructors  -->
      <VCol cols="12" md="4" sm="6">
        <AcademyCardPopularInstructors />
      </VCol>

      <!-- 👉 Academy Top Courses  -->
      <VCol cols="12" md="4" sm="6">
        <AcademyCardTopCourses />
      </VCol>

      <!-- 👉 Academy Upcoming Webinar -->
      <VCol cols="12" md="4" sm="6">
        <AcademyUpcomingWebinar />
      </VCol>

      <!-- 👉 Academy Assignment Progress  -->
      <VCol cols="12" md="4" sm="6">
        <AcademyAssignmentProgress />
      </VCol>

      <!-- 👉 Academy Course Table  -->
      <VCol>
        <AcademyCourseTable />
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/libs/apex-chart.scss";
</style>

<style lang="scss">
@use "@core-scss/template/libs/full-calendar";

.calendars-checkbox {
  .v-label {
    color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    opacity: var(--v-high-emphasis-opacity);
  }
}

.calendar-add-event-drawer {
  &.v-navigation-drawer:not(.v-navigation-drawer--temporary) {
    border-end-start-radius: 0.375rem;
    border-start-start-radius: 0.375rem;
  }

  &.v-navigation-drawer--temporary:not(.v-navigation-drawer--active) {
    transform: translateX(-110%) !important;
  }
}

.calendar-date-picker {
  display: none;

  +.flatpickr-input {
    +.flatpickr-calendar.inline {
      border: none;
      box-shadow: none;

      .flatpickr-months {
        border-block-end: none;
      }
    }
  }

  &~.flatpickr-calendar .flatpickr-weekdays {
    margin-block: 0 4px;
  }
}

@media screen and (max-width: 1279px) {
  .calendar-add-event-drawer {
    border-width: 0;
  }
}
</style>

<style lang="scss" scoped>
.v-layout {
  overflow: visible !important;

  .v-card {
    overflow: visible;
  }
}
</style>
