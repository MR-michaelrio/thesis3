const currentDate = new Date();
const attendanceDate = currentDate.toISOString().split('T')[0];
console.log(attendanceDate);