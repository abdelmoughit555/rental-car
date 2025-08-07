<script setup lang="ts">
    import { defineProps, computed } from 'vue';
    import { format } from 'date-fns';
    import { type ActivityLog } from '@/Types/ActivityLog';
    import { Card, CardContent } from '@/Components/shadcn/ui/card';
    import { Separator } from '@/Components/shadcn/ui/separator';
    import { Badge } from '@/Components/shadcn/ui/badge';
    import Artwork from '@/Components/Artwork.vue';
    import ActivityLogEvent from '@/Components/ActivityLog/ActivityLogEvent.vue';
    import ActivityLogData from '@/Components/ActivityLog/ActivityLogData.vue';
    import { Clock4, Bot } from 'lucide-vue-next';

    const props = defineProps<{
        activityLog: ActivityLog;
    }>();

    const formattedTime = computed(() => format(new Date(props.activityLog.created_at), 'h:mm a'));

    const shouldRenderActivityData = (value: { old?: any; new?: any }) => {
        return value.old || value.new;
    };
</script>

<template>
    <div class="flex">
        <div class="w-24 relative">
            <Separator class="absolute top-10" />
            <Separator orientation="vertical" class="absolute" />
        </div>
        <div class="flex gap-4 flex-1 my-5">
            <div class="flex flex-col">
                <ActivityLogEvent :event="activityLog.event" />
            </div>
            <div class="space-y-2 w-full pt-2">
                <div class="flex gap-2">
                    <Badge variant="outline" class="px-2 py-1">
                        {{ activityLog.event }}
                    </Badge>
                    <div class="flex text-sm font-medium text-muted-foreground items-center gap-2">
                        <Clock4 class="size-4"/>
                        {{ formattedTime }}
                    </div>
                </div>

                <template v-if="activityLog.causer">
                    <div class="flex items-center gap-2">
                        <Artwork 
                            :artworkUrl="activityLog.causer.profile_photo_url" 
                            :name="activityLog.causer.full_name" 
                            width="w-6" 
                            height="h-6" 
                        />
                        <p class="text-muted-foreground tx-sm">{{ activityLog.causer.full_name }}</p>
                    </div>
                </template>
                <template v-else>
                    <div class="flex items-center gap-2">
                        <div class="rounded-full p-1 bg-[#20293a]">
                            <Bot class="size-4" color="#F7FAFC"/>
                        </div>
                        <p class="text-muted-foreground tx-sm">System Change</p>
                    </div>
                </template>
                
                <div class="text-muted-foreground italic font-semibold capitalize">
                    {{ activityLog.subject_type }} {{ activityLog.event }}
                </div>

                <Card class="border rounded-lg">
                    <CardContent class="p-3">
                        <div class="space-y-2">
                            <template
                                v-for="(value, key) in activityLog.groupedProperties"
                                :key="key"
                            >
                                <div
                                    v-if="shouldRenderActivityData(value)"
                                    class="flex items-start gap-4"
                                >
                                    <div class="flex gap-5">
                                        <div class="font-semibold">{{ key }}:</div>
                                        <template v-if="activityLog.event === 'updated'">
                                            <ActivityLogData variant="old">{{ value.old ?? '--' }}</ActivityLogData>
                                            <ActivityLogData variant="new">{{ value.new ?? '--' }}</ActivityLogData>
                                        </template>
                                        <template v-if="activityLog.event === 'created'">
                                            <ActivityLogData variant="new">{{ value.new ?? '--' }}</ActivityLogData>
                                            <ActivityLogData>added</ActivityLogData>
                                        </template>
                                        <template v-if="activityLog.event === 'deleted'">
                                            <ActivityLogData variant="old">{{ value.old ?? '--' }}</ActivityLogData>
                                            <ActivityLogData>deleted</ActivityLogData>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
