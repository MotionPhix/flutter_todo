// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:ner_reports/data/database.dart';
import 'package:ner_reports/util/input_dialog.dart';
import 'package:ner_reports/util/todo_tile.dart';

class Todo extends StatefulWidget {
  const Todo({super.key});

  @override
  State<Todo> createState() => _TodoState();
}

class _TodoState extends State<Todo> {
  TextEditingController taskController = TextEditingController();
  final _myTaskBox = Hive.box('myTasksBox');
  TaskStorage db = TaskStorage();

  @override
  void initState() {
    if (_myTaskBox.get('TASKLIST') == null) {
      db.createInitialData();
    } else {
      db.loadData();
    }

    super.initState();
  }

  void toggleTask(bool? status, int index) {
    setState(() {
      db.tasks[index][1] = !db.tasks[index][1];
    });

    db.updateData();
  }

  void removeTask(int index) {
    setState(() {
      db.tasks.removeAt(index);
    });

    db.updateData();
  }

  void save() {
    if (taskController.text.isNotEmpty) {
      setState(() {
        db.tasks.add([taskController.text, false]);
        taskController.clear();
      });

      db.updateData();

      Navigator.of(context).pop();
    }
  }

  void cancel() {
    Navigator.of(context).pop();
  }

  void addTask() {
    showDialog(
      context: context,
      builder: (context) {
        return InputDialog(
          controller: taskController,
          onSave: save,
          onCancel: cancel,
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.yellow[200],
      appBar: AppBar(
        title: Text(
          'Quick task',
          style: TextStyle(
            fontWeight: FontWeight.w500,
          ),
        ),
        elevation: 0,
        backgroundColor: Colors.amber,
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: addTask,
        backgroundColor: Colors.amber,
        child: Icon(Icons.add),
      ),
      body: db.tasks.isNotEmpty
          ? ListView.builder(
              itemCount: db.tasks.length,
              itemBuilder: (context, index) {
                return TodoTile(
                  taskName: db.tasks[index][0],
                  taskCompleted: db.tasks[index][1],
                  onChanged: (value) => toggleTask(value, index),
                  deleteTask: (value) => removeTask(index),
                );
              },
            )
          : Center(
              child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(
                  Icons.hourglass_empty,
                  size: 64,
                ),
                Text(
                  'Oops!',
                  style: TextStyle(
                    fontSize: 24,
                  ),
                  textAlign: TextAlign.center,
                ),
                Text(
                  'You don\'t have any tasks yet!',
                  textAlign: TextAlign.center,
                )
              ],
            )),
    );
  }
}
