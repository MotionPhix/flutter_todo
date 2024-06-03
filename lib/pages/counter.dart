// ignore_for_file: prefer_const_constructors

import 'package:flutter/material.dart';

class Counter extends StatefulWidget {
  const Counter({super.key});

  @override
  State<Counter> createState() => _CounterState();
}

class _CounterState extends State<Counter> {
  int _counter = 0;

  void _incrementCounter() {
    setState(() {
      _counter++;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Ziyawa'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('You pressed the button this many times'),
            Text(
              _counter.toString(),
              style: TextStyle(fontSize: 42),
            ),
            ElevatedButton(
              onPressed: _incrementCounter,
              child: Text(
                'Increment',
              ),
            )
          ],
        ),
      ),
    );
  }
}
