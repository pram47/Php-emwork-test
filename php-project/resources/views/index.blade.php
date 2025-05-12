<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ระบบบันทึกผลการปฏิบัติงานประจำวัน</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body { font-family: Tahoma, sans-serif; margin: 2rem; }
        input, select { margin: 0.3rem; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        table, th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        button { margin: 0 0.3rem; }
    </style>
</head>
<body>
    <div id="app">
        <h1>ระบบบันทึกผลการปฏิบัติงานประจำวัน</h1>

        <h3>เพิ่มรายการ</h3>
        <form @submit.prevent="saveLog">
            <select v-model="form.job_type" required>
                <option value="">-- ประเภทงาน --</option>
                <option>Development</option>
                <option>Test</option>
                <option>Document</option>
            </select>

            <input type="text" v-model="form.job_name" placeholder="ชื่องาน" required>
            <input type="time" v-model="form.start_time" required>
            <input type="time" v-model="form.end_time" required>

            <select v-model="form.status" required>
                <option value="">-- สถานะ --</option>
                <option>ดำเนินการ</option>
                <option>เสร็จสิ้น</option>
                <option>ยกเลิก</option>
            </select>

            <input type="date" v-model="form.work_date" required>

            <button type="submit">[[ form.id ? 'อัปเดต' : 'บันทึก' ]]</button>
            <button v-if="form.id" @click="resetForm">ยกเลิก</button>
        </form>

        <h3>ค้นหาตามวันที่</h3>
        <input type="date" v-model="filterDate" @change="fetchLogs">

        <table v-if="logs.length">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>ประเภท</th>
                    <th>ชื่องาน</th>
                    <th>เริ่ม</th>
                    <th>สิ้นสุด</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="log in logs" :key="log.id">
                    <td>[[ log.work_date ]]</td>
                    <td>[[ log.job_type ]]</td>
                    <td>[[ log.job_name ]]</td>
                    <td>[[ log.start_time ]]</td>
                    <td>[[ log.end_time ]]</td>
                    <td>[[ log.status ]]</td>
                    <td>
                        <button @click="editLog(log)">✏️</button>
                        <button @click="deleteLog(log.id)">🗑️</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else>ไม่มีข้อมูล</div>

        <h3>รายงานสถานะประจำเดือน</h3>
        <input type="month" v-model="filterMonth" @change="fetchSummary">

        <ul>
            <li v-for="item in summary" :key="item.status">
                [[ item.status ]] : [[ item.total ]] รายการ
            </li>
        </ul>
    </div>

    <script>
        new Vue({
            el: '#app',
            delimiters: ['[[', ']]'],
            data: {
                form: {
                    id: null,
                    job_type: '',
                    job_name: '',
                    start_time: '',
                    end_time: '',
                    status: '',
                    work_date: ''
                },
                logs: [],
                filterDate: '',
                filterMonth: '',
                summary: []
            },
            created() {
                this.fetchLogs();
                this.fetchSummary();
            },
            methods: {
                resetForm() {
                    this.form = {
                        id: null, job_type: '', job_name: '',
                        start_time: '', end_time: '',
                        status: '', work_date: ''
                    };
                },
                fetchLogs() {
                    let url = '/api/work-logs';
                    if (this.filterDate) {
                        url += `?date=${this.filterDate}`;
                    }
                    axios.get(url).then(res => {
                        this.logs = res.data;
                    });
                },
                saveLog() {
                    const method = this.form.id ? 'put' : 'post';
                    const url = this.form.id
                        ? `/api/work-logs/${this.form.id}`
                        : '/api/work-logs';

                    axios[method](url, this.form)
                        .then(() => {
                            this.fetchLogs();
                            this.resetForm();
                        })
                        .catch(error => {
                            console.error('Save failed:', error.response?.data || error.message);
                            alert('เกิดข้อผิดพลาดในการบันทึก');
                        });
                }, 

                editLog(log) {
                    this.form = Object.assign({}, log);
                },
                deleteLog(id) {
                    if (confirm('ลบข้อมูลนี้หรือไม่?')) {
                        axios.delete(`/api/work-logs/${id}`).then(() => {
                            this.fetchLogs();
                        });
                    }
                },
                fetchSummary() {
                    let month = this.filterMonth || new Date().toISOString().slice(0, 7);
                    axios.get(`/api/work-logs/summary?month=${month}`)
                        .then(res => this.summary = res.data);
                }
            }

        });
    </script>
</body>
</html>
