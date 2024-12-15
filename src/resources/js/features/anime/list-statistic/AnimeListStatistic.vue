<script setup lang="ts">
import type { AnimeListStatistics } from '@/entities/anime-list';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/shared/ui/hover-card';
import { Progress } from '@/shared/ui/progress';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/shared/ui/table';

type Props = {
    animeListStatistic: AnimeListStatistics;
};

defineProps<Props>();
</script>

<template>
    <HoverCard :open-delay="50" :close-delay="0">
        <HoverCardTrigger class="cursor-pointer underline decoration-dotted">
            Who added this anime?
        </HoverCardTrigger>
        <HoverCardContent class="w-96" side="bottom" align="start" :side-offset="5">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Users</TableHead>
                        <TableHead>Percentage</TableHead>
                        <TableHead>Status</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="statistic in animeListStatistic"
                        :key="statistic.status"
                    >
                        <TableCell>
                            {{ statistic.user_count }}
                        </TableCell>
                        <TableCell>
                            <Progress :model-value="statistic.percentage" />
                        </TableCell>
                        <TableCell>
                            {{ statistic.status }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </HoverCardContent>
    </HoverCard>
</template>

<style scoped></style>
