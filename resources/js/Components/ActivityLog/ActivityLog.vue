<script setup lang="ts">
    import { ref, onMounted, computed, defineProps, watch, type Ref } from 'vue';
    import { format } from 'date-fns';
    import { Clock4, User, UserCog, Calendar, ListMinus, ArrowDown, ArrowUp, ScrollText } from 'lucide-vue-next';
    import Panel from '@/Components/Panel.vue';
    import ActivityLogItem from '@/Components/ActivityLog/ActivityLogItem.vue';
    import { castFromApi, type ActivityLog } from '@/Types/ActivityLog';
    import { Button } from '@/Components/shadcn/ui/button';
    import { Separator } from '@/Components/shadcn/ui/separator';
    import {
        FilterBar,
        FilterSelect,
        FilterDateRange,
    } from '@/Components/Filters';

    import pickBy from 'lodash/pickBy';
    import moment from 'moment';
    import { useActivityLog } from '@/stores/activityLog';

    interface FilterParams {
        logName?: string;
        page?: number;
        direction?: string | null;
        causer?: string;
        event?: string;
        subjectType?: string;
        createdAt?: Array<{ start: Date; end: Date }>;
        createdAtSince?: string;
        createdAtUntil?: string;
    }

    const activityLog = useActivityLog();
    type ActivityLogType =
        | 'paymentProfile'
        | 'policy'
        | 'profile'
        | 'report'
        | 'user'
        | 'composition'
        | 'deal'
        | 'delivery'
        | 'period'
        | 'recordLabel'
        | 'release'
        | 'reportTemplate'
        | 'statement'
        | 'track'
        | 'video';

    const props = withDefaults(defineProps<{
        type: ActivityLogType;
        identifier: string | number;
    }>(), {
        type: 'profile' as ActivityLogType,
    });

    const activityLogs: Ref<ActivityLog[]> = ref([]);
    const loading = ref(false);
    const hasMorePages = ref(true);
    const currentPage = ref(1);
    const direction = ref<string | null>(null);
    const users = ref<Array<{ id: number; name: string }> | null>(null);
    const subjectTypes = ref<Array<{ id: string; name: string }> | null>(null);
    const params = ref<FilterParams>({});
    const loadMoreTrigger = ref<HTMLElement | null>(null);

    const formatDate = (date: string) => format(new Date(date), 'MMM d, yyyy h:mm a');

    const groupedActivityLogs = computed(() => {
        const groups: { [key: string]: ActivityLog[] } = {};

        activityLogs.value.forEach(log => {
            const date = formatDate(log.created_at);
            if (!groups[date]) {
                groups[date] = [];
            }
            groups[date].push(log);
        });

        return groups;
    });

    const logNames = {
        paymentProfile: 'payment_profiles',
        policy: 'policies',
        profile: 'profiles',
        report: 'reports',
        user: 'users',
        composition: 'compositions',
        deal: 'deals',
        delivery: 'delivers',
        period: 'periods',
        recordLabel: 'record_labels',
        release: 'releases',
        reportTemplate: 'report_templates',
        statement: 'statements',
        track: 'tracks',
        video: 'videos'
    }

    const prepareFilters = (filters: FilterParams): FilterParams => {
        const preparedFilters = pickBy({
            logName: `${logNames[props.type]}.${props.identifier}`,
            subject_id: props.identifier,
            subject_type: props.type,
            page: currentPage.value,
            direction: direction.value,
            ...filters,
        });

        if (preparedFilters.createdAt) {
            preparedFilters.createdAtSince = moment(preparedFilters.createdAt[0].start).format('YYYY-MM-DD');
            preparedFilters.createdAtUntil = moment(preparedFilters.createdAt[0].end).format('YYYY-MM-DD');
            delete preparedFilters.createdAt;
        }

        return preparedFilters;
    };

    const fetchActivityLogs = async () => {
        if (loading.value) return;

        loading.value = true;

        try {
            const filters = prepareFilters(params.value);
            const { data } = await activityLog.fetchActivityLog(filters);

            users.value = data.users.map((user) => ({ id: user.id, name: user.full_name }));
            subjectTypes.value = data.subjectTypes.map(({ subject_type }) => ({ id: subject_type, name: subject_type }));

            const newLogs = data.activityLogs.map(castFromApi);
            activityLogs.value = currentPage.value === 1 ? newLogs : [...activityLogs.value, ...newLogs];

            hasMorePages.value = data.meta.current_page < data.meta.last_page;
        } catch (error) {
            console.error('Error fetching activity logs:', error);
        } finally {
            loading.value = false;
        }
    };

    const availableFilters = computed(() => [
        {
            id: 'causer',
            name: 'Users',
            component: FilterSelect,
            icon: User,
            props: {
                title: 'Users',
                items: users.value
            }
        }, {
            id: 'event',
            name: 'Events',
            component: FilterSelect,
            icon: UserCog,
            props: {
                title: 'Events',
                items: [{
                    id: 'created',
                    name: 'Created',
                }, {
                    id: 'updated',
                    name: 'Updated',
                }, {
                    id: 'deleted',
                    name: 'Deleted',
                }]
            }
        }, {
            id: 'subjectType',
            name: 'Subject Types',
            component: FilterSelect,
            icon: ListMinus,
            props: {
                title: 'Subject Types',
                items: subjectTypes.value
            }
        }, {
            id: 'createdAt',
            name: 'Created Date',
            component: FilterDateRange,
            icon: Calendar,
            props: {
                title: 'Created Date'
            }
        }
    ]);

    // Optimize infinite scroll with debounced observer
    onMounted(() => {
        fetchActivityLogs();

        const observer = new IntersectionObserver(
            (entries) => {
                const [entry] = entries;
                if (entry?.isIntersecting && hasMorePages.value && !loading.value) {
                    currentPage.value += 1;
                    fetchActivityLogs();
                }
            },
            { rootMargin: '300px' }
        );

        if (loadMoreTrigger.value) {
            observer.observe(loadMoreTrigger.value);
        }

        return () => {
            if (loadMoreTrigger.value) {
                observer.unobserve(loadMoreTrigger.value);
            }
        };
    });

    // Optimize watchers
    watch(params, () => {
        activityLogs.value = [];
        currentPage.value = 1;
        fetchActivityLogs();
    }, { deep: true });

    const sort = () => {
        direction.value = direction.value === 'asc' ? null : 'asc';
        activityLogs.value = [];
        currentPage.value = 1;
        fetchActivityLogs();
    };
</script>

<template>
    <Panel>
        <template #title>Recent Activity</template>
        <template #description>
            All of the recent activity for this {{ type }}
        </template>
        <template #content>
            <div class="flex justify-between">
                <FilterBar v-model="params" :availableFilters="availableFilters" class="mb-5" />
                <Button variant="secondary" @click="sort">
                    Created At
                    <ArrowUp v-if="direction === 'asc'" />
                    <ArrowDown v-else />
                </Button>
            </div>

            <template v-if="activityLogs.length">
                <div v-for="(logs, date) in groupedActivityLogs" :key="date" class="mb-6">
                    <div class="grid">
                        <div class="flex items-center gap-2">
                            <Clock4 class="size-5" />
                            {{ date }}
                        </div>
                        <div class="flex-1 ml-[9px]">
                            <ActivityLogItem v-for="activityLog in logs" :key="activityLog.id"
                                :activityLog="activityLog" />
                            <Separator />
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="text-center mt-5">
                    <ScrollText class="mx-auto size-8" />
                    <p class="mt-2">No Activity match this filter.</p>
                    <p class="text-muted-foreground">Try filtering a little differently.</p>
                </div>
            </template>

            <div v-if="hasMorePages" class="py-4 text-center">
                <div ref="loadMoreTrigger">
                    <div v-if="loading" class="text-center my-3">
                        <span class="spinner-border" role="status"></span>
                        Loading...
                    </div>
                </div>
            </div>
        </template>
    </Panel>


</template>
