// ignore_for_file: prefer_const_constructors

import 'package:flutter/material.dart';
import 'package:ner_reports/util/action_button.dart';

class InputDialog extends StatelessWidget {
  final controller;
  VoidCallback onSave;
  VoidCallback onCancel;

  InputDialog({
    super.key,
    required this.controller,
    required this.onSave,
    required this.onCancel,
  });

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: Text('Add new task'),
      backgroundColor: Colors.amber,
      content: TextField(
        controller: controller,
        decoration: InputDecoration(
          border: OutlineInputBorder(),
          hintText: "Enter a task",
        ),
      ),
      actions: [
        MyActionButton(
          onPressed: onSave,
          text: 'Add',
        ),
        TextButton(
          onPressed: onCancel,
          child: Text('Cancel'),
        ),
      ],
    );
  }
}
