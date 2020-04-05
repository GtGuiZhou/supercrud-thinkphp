<template>
    <div>
        <sp-crud-template
                :url="url" :form="form">
            <template v-slot:table>
                {foreach $propertyList as $key=>$val }
                    <el-table-column prop="{$key}" label="{$val.comment}"></el-table-column>
                {/foreach}
            </template>

            <template v-slot:form="{sForm,mode}">
                {foreach $propertyList as $key=>$val }
                    <el-form-item prop="{$key}" label="{$val.comment}">
                        {// 根据数据类型生成不同的组件}
                        {if $val.type == 'enum'}
                        {foreach $val.options as $item}
                        <el-radio v-model="sForm.{$key}" label="{$item}">{$item}</el-radio>
                        {/foreach}
                        {// set类型}
                        {elseif $val.type == 'set' /}
                        <el-checkbox-group v-model="sForm.{$key}">
                            {foreach $val.options as $item}
                                <el-checkbox label="{$item}"></el-checkbox>
                            {/foreach}
                        </el-checkbox-group>
                        {// 数值类型}
                        {elseif $val.type == 'int' || $val.type == 'bigint' || $val.type == 'smallint' || $val.type == 'float' || $val.type == 'double' || $val.type == 'decimal'/}
                        <el-input-number v-model="sForm.{$key}" placeholder="请输入{$val.comment}"></el-input-number>
                        {// text类型}
                        {elseif $val.type == 'text' /}
                        <el-input v-model="sForm.{$key}" placeholder="请输入{$val.comment}" autosize type="textarea" :rows="3"></el-input>
                        {// char类型}
                        {elseif $val.type == 'char' || $val.type == 'varchar' /}
                        <el-input v-model="sForm.{$key}" placeholder="请输入{$val.comment}" maxlength="{$val.length}"></el-input>
                        {// 日期时间类型}
                        {elseif $val.type == 'datetime' || $val.type == 'timestamp' /}
                        <el-date-picker v-model="sForm.{$key}" type="datetime" placeholder="选择{$val.comment}的日期时间"></el-date-picker>
                        {// 日期类型}
                        {elseif $val.type == 'date'  /}
                        <el-date-picker v-model="sForm.{$key}" type="date" placeholder="选择{$val.comment}的日期"></el-date-picker>
                        {// 时间类型}
                        {elseif $val.type == 'time' /}
                        <el-date-picker v-model="sForm.{$key}" type="date" placeholder="选择{$val.comment}的时间"></el-date-picker>
                        {// 其他类型}
                        {else}
                        <el-input v-model="sForm.{$key}" placeholder="请输入{$val.comment}"></el-input>
                        {/if}
                    </el-form-item>
                {/foreach}
            </template>
        </sp-crud-template>
    </div>
</template>

<script>
    import SpCrudTemplate from "@/components/SpCrudTemplate";

    export default {
        name: "Index",
        components: {SpCrudTemplate},
        data() {
            return {
                url: '{$url}',
                form: {
            {foreach $propertyList as $key=>$val }
            {if $val.type == 'int' || $val.type == 'bigint' || $val.type == 'smallint' || $val.type == 'float' || $val.type == 'double' || $val.type == 'decimal'}
            {$key}: {$val.default},
            {else}
            {$key}: '{$val.default}',
            {/if}
            {/foreach}
        }
            }
        },
        computed: {},
        mounted() {
        },
        methods: {}
    }
</script>

<style scoped>

</style>